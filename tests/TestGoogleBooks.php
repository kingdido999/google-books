<?php

require_once __DIR__ . '/../src/GoogleBooks/GoogleBooks.php';

use GoogleBooks\GoogleBooks;

class TestGoogleBooks extends PHPUnit_Framework_TestCase {

    public function testInvalidKey()
    {
        $key = 'AIzaSyD6Uers3Lug2GFdRpb9FCfftgA0e26fK';
        $query = '9780262033848';

        $book = new GoogleBooks($key);

        $this->assertFalse($book->searchByISBN($query));
    }

    public function testGetTitle()
    {
        $key = 'AIzaSyD6Uers3Lug2GFdRpb9FCfftgA0e26fKnc';
        $query = '9780262033848';

        $book = new GoogleBooks($key);
        $book->searchByISBN($query);

        $expected = 'Introduction to Algorithms';
        $actual = $book->getTitle();

        $this->assertEquals($expected, $actual);
    }

    public function testGetAuthors()
    {
        $key = 'AIzaSyD6Uers3Lug2GFdRpb9FCfftgA0e26fKnc';
        $query = '9780321573513';

        $book = new GoogleBooks($key);
        $book->searchByISBN($query);

        $expected = ["Robert Sedgewick", "Kevin Daniel Wayne"];
        $actual = $book->getAuthors();

        $this->assertEquals($expected, $actual);
    }

    public function testGetSmallThumbnail()
    {
        $key = 'AIzaSyD6Uers3Lug2GFdRpb9FCfftgA0e26fKnc';
        $query = '9780262033848';

        $book = new GoogleBooks($key);
        $book->searchByISBN($query);

        $expected = 'http://books.google.com/books/content?id=i-bUBQAAQBAJ&printsec=frontcover&img=1&zoom=5&edge=curl&source=gbs_api';
        $actual = $book->getSmallThumbnail();

        $this->assertEquals($expected, $actual);
    }

    public function testGetLanguage()
    {
        $key = 'AIzaSyD6Uers3Lug2GFdRpb9FCfftgA0e26fKnc';
        $query = '9780262033848';

        $book = new GoogleBooks($key);
        $book->searchByISBN($query);

        $expected = 'English';
        $actual = $book->getLanguage();

        $this->assertEquals($expected, $actual);
    }

    public function testGetIsbn10()
    {
        $key = 'AIzaSyD6Uers3Lug2GFdRpb9FCfftgA0e26fKnc';
        $query = '9780262033848';

        $book = new GoogleBooks($key);
        $book->searchByISBN($query);

        $expected = '0262033844';
        $actual = $book->getIsbn10();

        $this->assertEquals($expected, $actual);
    }
}
