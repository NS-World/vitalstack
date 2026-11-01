## spark-why-big-data
Q: Spark speeds up big jobs by...
- [x] Splitting data across many machines and processing in parallel
- [ ] Using one bigger CPU only
- [ ] Compressing all data into one file
> Work is divided into tasks on a cluster.
Q: Spark transformations are 'lazy', meaning...
- [x] They only run when an action like count() or show() is called
- [ ] They are slow
- [ ] They never run
> Spark builds a plan and optimises it before running.
Q: When is Spark overkill?
- [x] When the data fits comfortably in pandas on one machine
- [ ] When data is many terabytes
- [ ] When you need distributed processing
> Small data is simpler and faster in pandas.

## spark-dataframes-sql
Q: df.groupBy('city').agg(F.sum('sales')) is like SQL's...
- [x] SELECT city, SUM(sales) ... GROUP BY city
- [ ] DELETE FROM sales
- [ ] CREATE TABLE city
> Spark DataFrames and Spark SQL do the same work.
Q: To query a DataFrame with SQL you first...
- [x] Register it with createOrReplaceTempView
- [ ] Convert it to CSV
- [ ] Restart Spark
> Then spark.sql('SELECT ...') can use it.
Q: Parquet files are popular with Spark because they are...
- [x] Columnar and compressed, so reads are fast
- [ ] Human-readable text
- [ ] Always tiny
> Spark reads only the columns it needs.

## spark-mllib
Q: VectorAssembler is used to...
- [x] Combine feature columns into one vector column
- [ ] Draw charts
- [ ] Split strings
> Spark ML models expect a single features column.
Q: A Spark ML Pipeline chains...
- [x] Feature steps and a model into one fit/transform object
- [ ] Only SQL queries
- [ ] Email notifications
> Like scikit-learn pipelines, but distributed.
Q: Why evaluate with a held-out split in Spark too?
- [x] To check the model works on unseen data
- [ ] Spark requires it to run
- [ ] It makes training faster
> Big data doesn't remove overfitting.
