<?php declare(strict_types=1);

namespace PHPDeferrable\Test;
use PHPDeferrable\Defer;
use PHPUnit\Framework\TestCase;

class DeferCreateContextClassTestTestMyClass
{
    public function doSomething1()
    {
        $context = Defer::createContext();
        $context->defer(function () {
            echo "Two!\n";
        });

        $context->defer(function () {
            echo "Three!\n";
        });
        echo "One!\n";
    }

    public function doSomething2()
    {
        $context = Defer::createContext();
        $context->defer(function () {
            echo "NyanNyan!\n";
        });
        echo "Wanwan!\n";
    }

    public function doSomething3()
    {
        $context = Defer::createContext();
        $handle = fopen('php://memory', 'r');
        $context->defer(function () use ($handle) {
            fclose($handle);
        });
        return 'Return value';
    }
}

class DeferCreateContextClassTest extends TestCase
{
    public function testDeferPattern1()
    {
        ob_start();
        $myClass = new DeferCreateContextClassTestTestMyClass();
        $myClass->doSomething1();
        $myClass->doSomething2();
        $result = ob_get_clean();

        $this->assertSame(
            "One!\nThree!\nTwo!\nWanwan!\nNyanNyan!\n",
            $result
        );
    }

    public function testDeferPattern2()
    {
        ob_start();
        $myClass = new DeferCreateContextClassTestTestMyClass();
        $myClass->doSomething2();
        $result = ob_get_clean();

        $this->assertSame(
            "Wanwan!\nNyanNyan!\n",
            $result
        );
    }

    public function testDeferPattern3()
    {
        ob_start();
        $myClass = new DeferCreateContextClassTestTestMyClass();
        $myClass->doSomething1();
        $result = ob_get_clean();

        $this->assertSame(
            "One!\nThree!\nTwo!\n",
            $result
        );
    }

    public function testDeferPattern4()
    {
        $myClass = new DeferCreateContextClassTestTestMyClass();
        $result = $myClass->doSomething3();

        $this->assertSame(
            "Return value",
            $result
        );
    }
}
