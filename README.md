# Google Books API

Google Books API for PHP >= 5.3.0. It currently only supports searching a specific book by ISBN.

## Usage

```php
$key = 'YOUR_API_KEY';
$query = '084930315X';  // 10 or 13 digits ISBN

$book = new GoogleBooks\GoogleBooks($key);

if ($book->searchByISBN($query))
{
    // success
    $title = $book->getTitle();
}
else
{
    // error
}
```
