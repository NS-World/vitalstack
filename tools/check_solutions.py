#!/usr/bin/env python3
"""Compile and run every interview-problem solution in Java, C++ and JavaScript
and check its output matches the lesson's Python solution.

Usage: python3 tools/check_solutions.py [theme_dir] [slug ...]

For each content/lessons/dsa-problems/*.md lesson the Python solution block
(under the chosen "## Solution" heading) is run to get the expected output.
Outputs are compared after normalising formatting differences between
languages only: whitespace, quote style, True/true and None/null.
"""
import json, os, re, shutil, subprocess, sys, tempfile

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
THEME = os.path.join(ROOT, 'wp-content/themes/vitalstack')
LESSONS = os.path.join(ROOT, 'content/lessons/dsa-problems')


def pick_solution(md):
    """Return (heading, python code) of the solution the tabs attach to."""
    heads = [(m.start(), m.group(1).strip()) for m in re.finditer(r'^## (Solution\b.*)$', md, re.M)]
    if not heads:
        return None, None
    exact = [h for h in heads if h[1] == 'Solution']
    numbered = [h for h in heads if re.match(r'Solution \d', h[1])]
    pos, heading = exact[0] if exact else (numbered[-1] if numbered else heads[0])
    m = re.search(r'```python\n(.*?)```', md[pos:], re.S)
    return heading, (m.group(1) if m else None)


def norm(s):
    s = s.replace("'", '"')
    s = re.sub(r'\bTrue\b', 'true', s)
    s = re.sub(r'\bFalse\b', 'false', s)
    s = re.sub(r'\bNone\b', 'null', s)
    return re.sub(r'\s+', '', s)


def run(cmd, cwd, timeout=60):
    p = subprocess.run(cmd, cwd=cwd, capture_output=True, text=True, timeout=timeout)
    return p.returncode, p.stdout, p.stderr


def main():
    args = sys.argv[1:]
    theme = THEME
    if args and os.path.isdir(args[0]):
        theme = args.pop(0)
    sol_dir = os.path.join(theme, 'data/solutions')
    only = set(args)
    fails, checked = [], 0
    manifest = {}
    for name in sorted(os.listdir(LESSONS)):
        if not name.endswith('.md'):
            continue
        md = open(os.path.join(LESSONS, name), encoding='utf-8').read()
        slug = re.search(r'^slug:\s*(\S+)', md, re.M).group(1)
        if only and slug not in only:
            continue
        heading, py = pick_solution(md)
        manifest[slug] = heading
        if not py:
            fails.append(f'{slug}: no python solution')
            continue
        tmp = tempfile.mkdtemp(prefix='vs-sol-')
        try:
            open(os.path.join(tmp, 'sol.py'), 'w').write(py)
            rc, expected, err = run([sys.executable, 'sol.py'], tmp)
            if rc:
                fails.append(f'{slug}: python failed\n{err}')
                continue
            src = os.path.join(sol_dir, slug)
            for f in ('Main.java', 'main.cpp', 'main.js'):
                if not os.path.isfile(os.path.join(src, f)):
                    fails.append(f'{slug}: missing {f}')
                    continue
                shutil.copy(os.path.join(src, f), tmp)
            steps = {
                'java': ([['javac', '-Xlint:all', '-Werror', 'Main.java']], ['java', '-cp', '.', 'Main']),
                'cpp': ([['g++', '-std=c++17', '-O2', '-Wall', '-Wextra', '-Werror', '-o', 'main_cpp', 'main.cpp']], ['./main_cpp']),
                'js': ([['node', '--check', 'main.js']], ['node', 'main.js']),
            }
            for lang, (builds, exe) in steps.items():
                ok = True
                for b in builds:
                    rc, out, err = run(b, tmp, 120)
                    if rc:
                        fails.append(f'{slug} [{lang}] build failed\n{out}{err}')
                        ok = False
                        break
                if not ok:
                    continue
                rc, out, err = run(exe, tmp)
                checked += 1
                if rc:
                    fails.append(f'{slug} [{lang}] run failed\n{err}')
                elif norm(out) != norm(expected):
                    fails.append(f'{slug} [{lang}] output differs\n--- python\n{expected}--- {lang}\n{out}')
        finally:
            shutil.rmtree(tmp, ignore_errors=True)
    for f in fails:
        print('FAIL', f)
    print(f'{checked} runs, {len(manifest)} problems, {len(fails)} failures')
    sys.exit(1 if fails else 0)


if __name__ == '__main__':
    main()
