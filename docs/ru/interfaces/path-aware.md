# Интерфейс PathAwareInterface и его стандартная реализация

Интерфейс `Runn\Fs\PathAwareInterface` и его стандартная реализация `Runn\Fs\PathAwareTrait` 
позволяют создавать объекты, имеющие внутреннее свойство "путь", понимаемое, как "путь в иерархической файловой системе".

## Метод setPath()

Метод `setPath()` задает путь. Для пути можно задать необязательный префикс, который будет подставлен спереди пути.

Пример без префикса:

```php
use Runn\Fs\PathAwareInterface;
use Runn\Fs\PathAwareTrait;

$obj = new class implements PathAwareInterface 
{
    use PathAwareTrait;
}

$obj->setPath('/foo/bar'); // будет установлен путь /foo/bar
```

Пример с префиксом:

```php
use Runn\Fs\PathAwareInterface;
use Runn\Fs\PathAwareTrait;

$obj = new class implements PathAwareInterface 
{
    use PathAwareTrait;
}

$obj->setPath('/bar', '/foo'); // будет установлен путь /foo/bar
```

## Метод getPath()

Метод `getPath()` возвращает ранее заданный путь. Он может быть как "абсолютным" (то есть таким, какой
хранится в объекте), так и "относительным" - относительно заданного префикса пути.

Примеры:

```php
use Runn\Fs\PathAwareInterface;
use Runn\Fs\PathAwareTrait;

$obj = new class implements PathAwareInterface 
{
    use PathAwareTrait;
}

echo $obj->getPath() // выведется пустая строка

$obj->setPath('/foo/bar');
 
echo $obj->getPath() // выведется /foo/bar
echo $obj->getPath('/foo') // выведется /bar
```
