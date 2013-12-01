Lane Restrictions API
=====================

API for fetching and creating lane restriction data.

## Notes

- The api returns json data
- queries are performed via url params
- POST data for creating lane restrictions should be standard key/value post data

## Endpoints

/lanerestrictions/api/lanerestrictions

## Url params

### limit

Specifies maximum number of matching results to be returned

Default: 20

example
```/lanerestrictions/api/lanerestrictions?limit=2```

### offset

Specifies the point to start returning results from in the matching results

Default: 0

example
```/lanerestrictions/api/lanerestrictions?offset=4```

### starts_at

Specifies that only results with a starts_at value more recent (or exactly the
same) than the given ISO8601 date/time should be returned.
Note. starts_at must be used in conjunction with ends_at

Default: the current ISO8601 date/time

example
```/lanerestrictions/api/lanerestrictions?starts_at=2013-10-01T10:00:00+0000&ends_at=2013-10-03T09:30:00+0000```

### ends_at

Specifies that only results with an ends_at value less recent (or exactly the
same) than the given ISO8601 date/time should be returned.
Note. ends_at must be used in conjunction with starts_at

Default: 2 weeks from the current ISO8601 date/time

example
```/lanerestrictions/api/lanerestrictions?starts_at=2013-10-01T10:00:00+0000&ends_at=2013-10-03T09:30:00+0000```

### node_id

Specifies that only lane restriction records with the given open street maps
node id should be returned

Default: null

example
```/lanerestrictions/api/lanerestrictions?node_id=12312341```

#### node_ids

Specifies that only lane restriction records with the given open street maps
node ids should be returned. node ids should be given as a json encoded array
eg. [1213124,123123,12312312]

Default: null

example
```/lanerestrictions/api/lanerestrictions?node_ids=[12312341,12345678]```


#### path

Specifies that records should only be returned if they have A and B open street map
node values that match the given open street maps node id pair values being queried.
A node id pair should be passed in as a json encoded array eg. [1231245,1232131]

Records in the db will then be checked to see if they have an A node that matches either
of the 2 node ids in the array AND a B node that matches the other. Order is not important,
however the record must match on BOTH A and B (1231245 and 1232131) otherwise it
will not be considered a match.

Default: null

example
```/lanerestrictions/api/lanerestrictions?path=[12312341,12345678]```

#### path_ids

Basically the same as for 'path' described above except that it will accept an
array of path arrays and try to match each one. This way you can pass in a list
of paths and be returned any matching records with lane restrictions in place.
Be sure to use ```has_restrictions=true``` (defined below) to ensure no false
positives.

Default: null

example
```/lanerestrictions/api/lanerestrictions?path=[[12122341,12455678],[12312341,12345678],[12982341,12340478]]```

#### type

Specifies which type of lane restriction records to return.
Currently there are 2 types of lane restrictions, TrafficManagementPlan and
RoadClosure. These refer to the internal db tables of the same name.

Default: all types

example
```/lanerestrictions/api/lanerestrictions?type=RoadClosure```
or
```/lanerestrictions/api/lanerestrictions?type=TrafficManagementPlan```

#### type_id

In conjunction with ```type``` a type_id can be specified which must correspond
to an id in the table specified by 'type' eg. If you specify type=RoadClosure
and ```type_id=123```, if there is a record with ```type_id``` 123 and type ```RoadClosure```
then that record will be returned.

Note: you must specify the ```type``` param if you specify the ```type_id``` param

Default: null

example
```/lanerestrictions/api/lanerestrictions?type=RoadClosure&type_id=123```

#### type_ids

Instead of specifying a single ```type_id``` you may instead specify multiple
```type_ids``` through this param. ```type_ids``` should be specified as a
json encoded array eg. ```[113,124,125]``` and as with ```type_id``` you must
specify ```type``` if you specify ```type_ids```

Default: null

example
```/lanerestrictions/api/lanerestrictions?type=RoadClosure&type_ids=[123,124,125]```

#### has_restrictions

Specifies that only lane restriction records that have either speed limits in
place or lanes closed in 1 or both directions should be returned. You will
generally want to use this flag as records without any of the aforementioned
restrictions will probably not be very useful.

Note. you may specify true with either a '1' or 'true' value. Anything else is
considered false.

Default: false, all records will be returned regardless of what lane restriction
values they have

example
```/lanerestrictions/api/lanerestrictions?has_restrictions=true```
or
```/lanerestrictions/api/lanerestrictions?has_restrictions=1```