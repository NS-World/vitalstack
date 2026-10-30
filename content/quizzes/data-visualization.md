## data-visualization-introduction
Q: Which chart shows change over time?
- [x] Line chart
- [ ] Pie chart
- [ ] Histogram
> Time on the x-axis → line chart.
Q: In Matplotlib, what is an Axes?
- [ ] The whole image
- [x] One chart area with its own x- and y-axis
- [ ] The title
> A Figure can hold several Axes.
Q: When should you call `plt.savefig()`?
- [x] Before `plt.show()`
- [ ] After `plt.show()`
- [ ] Never with show
> show() may clear the figure.

## matplotlib-line-bar-charts
Q: How do you show the names of several lines?
- [ ] `ax.names()`
- [x] Give each line a `label` and call `ax.legend()`
- [ ] Add a title
> The legend lists the labels.
Q: Where should a bar chart's y-axis start?
- [x] At zero
- [ ] Just below the smallest bar
- [ ] It doesn't matter
> Otherwise bar heights exaggerate differences.
Q: Which chart suits long category names?
- [ ] Line chart
- [x] Horizontal bar chart (`barh`)
- [ ] Scatter plot
> Long labels fit on the side.

## matplotlib-histograms-scatter-subplots
Q: What does a histogram show?
- [ ] Change over time
- [x] How values are spread across ranges (bins)
- [ ] Parts of a whole
> It counts values in each bin.
Q: Dots on a scatter plot rise from left to right. This suggests…
- [x] A positive relationship
- [ ] No relationship
- [ ] That x causes y
> Relationship isn't proof of cause.
Q: What does `plt.subplots(1, 2)` create?
- [ ] One chart with two lines
- [x] A figure with two charts side by side
- [ ] Two separate windows
> 1 row, 2 columns of axes.

## seaborn-basics
Q: What does `sns.barplot` show by default?
- [ ] The count of rows
- [x] The mean of y for each category, with error bars
- [ ] The maximum value
> Use countplot for counts.
Q: What does `hue="gender"` add?
- [x] A colour split by the gender column
- [ ] A title
- [ ] A filter
> hue adds a second category.
Q: In a box plot, the line inside the box is the…
- [ ] Mean
- [x] Median
- [ ] Maximum
> The box covers the middle 50%.

## seaborn-relationships-heatmaps
Q: A correlation of -0.8 means…
- [ ] No relationship
- [x] When one rises, the other tends to fall, strongly
- [ ] One causes the other
> Close to -1 = strong negative relationship.
Q: What does `sns.regplot` add to a scatter plot?
- [x] A fitted trend line
- [ ] Colours by category
- [ ] A histogram
> It fits a linear regression line.
Q: Why use `vmin=-1, vmax=1` in a correlation heatmap?
- [ ] To make it faster
- [x] So colours are on an honest fixed scale
- [ ] To hide negative values
> Weak correlations won't look as strong as big ones.
