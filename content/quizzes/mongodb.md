## mongodb-documents
Q: MongoDB stores data as...
- [x] JSON-like documents in collections
- [ ] Rows in fixed-schema tables only
- [ ] Plain text files
> Documents can hold nested fields and arrays.
Q: When is embedding data inside a document a good choice?
- [x] When the data is read together and stays small
- [ ] Always, for everything
- [ ] When it changes independently and grows without limit
> Embed what you read together; reference what grows or is shared.
Q: Every MongoDB document has a unique...
- [x] _id field
- [ ] row number
- [ ] table name
> MongoDB creates an ObjectId _id if you don't set one.

## mongodb-crud-pymongo
Q: Which pymongo call finds all orders with status 'shipped'?
- [x] db.orders.find({"status": "shipped"})
- [ ] db.orders.select("shipped")
- [ ] db.orders.get(status=shipped)
> find() takes a filter document.
Q: Which operator updates one field without replacing the document?
- [x] $set
- [ ] $replace
- [ ] $all
> update_one(filter, {"$set": {...}}) changes only those fields.
Q: Why create an index on a field you filter by often?
- [x] To make those queries much faster
- [ ] To store more data
- [ ] To encrypt the field
> Indexes avoid scanning every document.

## mongodb-aggregation
Q: An aggregation pipeline is...
- [x] A list of stages that transform documents step by step
- [ ] A backup command
- [ ] A type of index
> Stages like $match, $group and $sort run in order.
Q: Which stage is like SQL's GROUP BY?
- [x] $group
- [ ] $match
- [ ] $limit
> $group combines documents and computes totals or averages.
Q: Why put $match early in a pipeline?
- [x] To filter documents before expensive stages
- [ ] It must always be last
- [ ] It sorts the results
> Less data flows into later stages.
