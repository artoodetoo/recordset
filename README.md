# artoodetoo/recordset

Work with array as a set of records.

## Basic Examples

Sort records like SQL does: 

```php
use R2\Helpers\ArrayRecordset;

$records = [
    ['name' => 'Alfred', 'age' => 40],
    ['name' => 'Mark',   'age' => 40],
    ['name' => 'Lue',    'age' => 45],
    ['name' => 'Ameli',  'age' => 38],
    ['name' => 'Barb',   'age' => 38],
];
$result = (new ArrayRecordset($records))
    ->orderBy('age', 'desc')
    ->orderBy('name', 'asc')
    ->get();

// will be equal to 
//    [
//        ['name' => 'Lue',    'age' => 45],
//        ['name' => 'Alfred', 'age' => 40],
//        ['name' => 'Mark',   'age' => 40],
//        ['name' => 'Ameli',  'age' => 38],
//        ['name' => 'Barb',   'age' => 38],
//    ];
```

Get only one column result:  
```php
$result = (new ArrayRecordset($records))
    ->pluck('name);

// will be equal to
// ['Alfred', 'Mark', 'Lue', 'Ameli', 'Barb'] 

$result = (new ArrayRecordset($records))
    ->pluck('age', 'name);

// will be equal to
// ['Alfred' => 40, 'Mark' => 40, 'Lue' => 45, 'Ameli' => 38, 'Barb' => 38] 
```

Of course you can combine orderBy and pluck. Also you can specify  
`CASE_SENSITIVE` and/or `PRESERVE_KEYS`  
flags in class constructor. See unit tests for detailed examples.

## Contributing

The project is open-sourced software. Issue reports and PRs are welcome.

## License

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
Issue reports and PRs are welcome.
