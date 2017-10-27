
# 安装

```shell
$ composer require "simple-shop/laravel-shop-user"
```

  or add the following line to your project's `composer.json`:

```json
"require": {
    "simple-shop/laravel-shop-user": "1.0.*"
}
```

then

```shell
$ composer update
```

After completion of the above, add the follow line to the section `providers` of `config/app.php`:

```php
SimpleShop\Cart\ServiceProvider::class,
```

And add the follow line to the section `aliases`:

```php
'Cart'      => SimpleShop\Cart\Facades\Facade::class,
```

# 使用

### 添加购物车

Add a new item.

```php
Item | null Cart::add(
                    string | int $id,
                    string $name,
                    int $quantity,
                    int | float $price
                    [, array $attributes = []]
                 );
```

**example:**

```php
$row = Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
// Item:
//    id       => 37
//    name     => 'Item name'
//    qty      => 5
//    price    => 100.00
//    color    => 'red'
//    size     => 'M'
//    total    => 500.00
//    __raw_id => '8a48aa7c8e5202841ddaf767bb4d10da'
$rawId = $row->rawId();// get __raw_id
$row->qty; // 5
...
```

### 修改购物车

Update the specified item.

```php
Item Cart::update(string $rawId, int $quantity);
Item Cart::update(string $rawId, array $arrtibutes);
```

**example:**

```php
Cart::update('8a48aa7c8e5202841ddaf767bb4d10da', ['name' => 'New item name');
// or only update quantity
Cart::update('8a48aa7c8e5202841ddaf767bb4d10da', 5);
```

### 获取购物车

Get all the items.

```php
Collection Cart::all();
```

**example:**

```php
$items = Cart::all();
```


### 获取单个购物车

Get the specified item.

```php
Item Cart::get(string $rawId);
```

**example:**

```php
$item = Cart::get('8a48aa7c8e5202841ddaf767bb4d10da');
```

### 移除购物车

Remove the specified item by raw ID.

```php
boolean Cart::remove(string $rawId);
```

**example:**

```php
Cart::remove('8a48aa7c8e5202841ddaf767bb4d10da');
```

### 清空购物车 cart

Clean Shopping Cart.

```php

boolean Cart::clean(); 
```

**example:**

```php
Cart::clean();
```

###统计金额

Returns the total of all items.

```php
int | float Cart::total(); // alias of totalPrice();
int | float Cart::totalPrice();
```

**example:**

```php
$total = Cart::total();
// or
$total = Cart::totalPrice();
```


###得到购物车条数

Return the number of rows.

```php
int Cart::countRows();
```

**example:**

```php
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(127, 'foobar', 15, 100.00, ['color' => 'green', 'size' => 'S']);
$rows = Cart::countRows(); // 2
```


### 获取商品数量

Returns the quantity of all items

```php
int Cart::count($totalItems = true);
```

`$totalItems` : When `false`,will return the number of rows.

**example:**

```php
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
$count = Cart::count(); // 11 (5+1+5)
```

### 搜索购物车

Search items by property.

```php
Collection Cart::search(array $conditions);
```

**example:**

```php
$items = Cart::search(['color' => 'red']);
$items = Cart::search(['name' => 'Item name']);
$items = Cart::search(['qty' => 10]);
```

### 是否为空

```php
bool Cart::isEmpty();
```

### Specifies the associated model

Specifies the associated model of item.

```php
Cart Cart::associate(string $modelName);
```

**example:**

```php
Cart::associate('App\Models\Product');
$item = Cart::get('8a48aa7c8e5202841ddaf767bb4d10da');
$item->product->name; // $item->product is instanceof 'App\Models\Product'
```


# Item


- `id`       - your goods item ID.
- `name`     - Name of item.
- `qty`      - Quantity of item.
- `price`    - Unit price of item.
- `total`    - Total price of item.
- `__raw_id` - Unique ID of row.
- `__model`  - Name of item associated Model.
- ... custom attributes.

And methods:

 - `rawId()` - Return the raw ID of item.

# 事件监听

| Event Name | Parameters |
| -------  | ------- |
| `cart.adding`  | ($attributes, $cart); |
| `cart.added`  | ($attributes, $cart); |
| `cart.updating`  | ($row, $cart); |
| `cart.updated`  | ($row, $cart); |
| `cart.removing`  | ($row, $cart); |
| `cart.removed`  | ($row, $cart); |
| `cart.destroying`  | ($cart); |
| `cart.destroyed`  | ($cart); |

You can easily handle these events, for example:

```php
Event::on('cart.adding', function($attributes, $cart){
    // code
});
```

# License

MIT