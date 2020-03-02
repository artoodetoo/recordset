# Work with array as a set of records.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/artoodetoo/recordset.svg?style=flat-square)](https://packagist.org/packages/artoodetoo/recordset)
[![Build Status](https://img.shields.io/travis/artoodetoo/recordset/master.svg?style=flat-square)](https://travis-ci.org/artoodetoo/recordset)
[![Quality Score](https://img.shields.io/scrutinizer/g/artoodetoo/recordset.svg?style=flat-square)](https://scrutinizer-ci.com/g/artoodetoo/recordset)
[![Total Downloads](https://img.shields.io/packagist/dt/artoodetoo/recordset.svg?style=flat-square)](https://packagist.org/packages/artoodetoo/recordset)

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

## Advanced example

You can extend this class by your own macro. Thanks a lot to amazing [spatie trait](https://github.com/artoodetoo/recordset).

```php
ArrayRecordset::macro(
    'orderByKeepOnTop',
    function (string $field, string $onTop, string $direction = 'asc') {
        $sign = strtolower($direction) === 'asc' ? 1 : -1;
        $this->comparators[] = function ($a, $b) use ($field, $onTop, $sign) {
            $r = ($b[$field] == $onTop) <=> ($a[$field] == $onTop);
            return $r == 0 ? strnatcasecmp($a[$field], $b[$field]) * $sign : $r;
        };
        return $this;
    }
);

// Whenever you sort by ascending or descending order, 
// Barb will be on top of the list!
$result = (new ArrayRecordset($this->nameAge))
    ->orderByKeepOnTop('name', 'Barb', 'asc')
    ->get();

// will be equal to 
// [
//     ['name' => 'Barb',   'age' => 38],
//     ['name' => 'Alfred', 'age' => 40],
//     ['name' => 'Ameli',  'age' => 38],
//     ['name' => 'Lue',    'age' => 45],
//     ['name' => 'Mark',   'age' => 40],
// ]
```
 
## Contributing

The project is open-sourced software. Issue reports and PRs are welcome.

## License

The project is open-sourced software licensed under the [MIT license](./LICENSE.md).
