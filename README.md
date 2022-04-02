# Data Transfer Object

You can use `transfers` always if you need to transfer data. You can use the transfer not only for transfer data, but also like a new layer of the system.

## Transfer

Create your first transfer.
```
class ExampleTransfer extend \Rbz\Data\Transfer
{
    public string $property_string;
    public integer $property_integer;
    public array $property_array;
}
```

### Load

Get associative array and put them to the `load()` method.

```
$transfer = new ExampleTransfer();

$associativeArray = [
    'property_string' => 'example',
    'property_integer' => 1234,
    'property_array' => []
]

$transfer->load($associativeArray);
```

Now you can get properties from the ExampleTransfer.

```
$transfer->property_string; // return example
$transfer->property_integer; // return 1234
$transfer->property_array; // return []
```

### Errors

If you are not sure the data was loaded correctly. You can use the `getErrors ()` method to get errors.

```
$transfer->getErrors();
```

### Validation

But if you are still not sure that your transfer is loaded correctly. Use the `validate()` method.
That will check properties is `present`. Also, you can write rules for properties just override the `rules()` method.

```
class ExampleTransfer extend \Rbz\Data\Transfer
{
    ...
    
    public function rules(): array
    {
        return [
            'property_string' => 'string|min:3',
            'property_integer' => 'integer',
            'property_array' => 'require|array'
        ];
    }
}
```

Then run validation again.

```
$transfer->validate();
```

You can partially check properties, just put array with necessary properties to the `validate()` method.

```
$transfer->validate(['property_string']); // only property_string
$transfer->validate(['!property_string']); // to exclude property_string
```


## Composite

Create your first composite transfer.

```
/**
 * @property ExampleTransfer $internalTransfer
 */
class ExampleCompositeTransfer extend \Rbz\Data\CompositeTransfer
{
    public string $example_property;
    
    public function __construct()
    {
        $this->internalTransfer = new ExampleTransfer();
    }
    
    public function internalTransfers(): array
    {
        return ['internalTransfer'];
    }
}
```

### Load

You have two ways to load the `composite transfer`.

First way:
```
$transfer = new ExampleCompositeTransfer();

$associativeArray = [
    'property_string' => 'example',
    'property_integer' => 1234,
    'property_array' => [],
    'example_property' => 'example composite string',
]

$transfer->load($associativeArray);
```

Second way:
```
$transfer = new ExampleCompositeTransfer();

$associativeArray = [
    'example_property' => 'example composite string',
    'internalTransfer' => [
        'property_string' => 'example',
        'property_integer' => 1234,
        'property_array' => [],
    ]
]

$transfer->load($associativeArray);
```

### Validation

```
$transfer->validate(['internalTransfer.property_string']); // validate only internalTransfer property_string
$transfer->validate(['internalTransfer.!property_string']); // validate everything except internalTransfer property_string
```

## Collecting

```
class ExampleCollectingTransfer extends CompositeTransfer
{
    /** @var ExampleTransfer[]  */
    public array $exampleTransfer = [];

    /** @var Collection[] */
    public array $collections = [];

    public function collectable(): array
    {
        return [
            'exampleTransfer' => ExampleTransfer::class,
            'collections' => Collection::class,
        ];
    }
}
```

### Load

```
$transfer = new ExampleCollectingTransfer();

$associativeArray = [
    'exampleTransfer' => [
        [
            'property_string' => 'example1',
            'property_integer' => 1,
            'property_array' => [],
        ],
        [
            'property_string' => 'example2',
            'property_integer' => 2,
            'property_array' => [
                'key' => 'value'
            ],
        ],
    ],
    'collections' => [
        [
            'random',
            'data,
        ],
        [
            'another',
            'random',
            'data,
        ],
    ]
]

$transfer->load($associativeArray);
```
