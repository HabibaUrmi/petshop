<?php
session_start();


abstract class Pet
{
  protected $name, $age;

  public function __construct($name, $age)
  {
    $this->name = htmlspecialchars($name);
    $this->age = (int)$age;
  }

  public function getInfo()
  {
    return "{$this->name}, Age: {$this->age}";
  }

  abstract public function speak();
}


class Dog extends Pet
{
  public function speak()
  {
    return "{$this->name} says: Woof!";
  }
}
class Cat extends Pet
{
  public function speak()
  {
    return "{$this->name} says: Meow!";
  }
}
class Bird extends Pet
{
  public function speak()
  {
    return "{$this->name} says: Tweet!";
  }
}


class PetShop
{
  public function __construct()
  {
    if (!isset($_SESSION['pets'])) $_SESSION['pets'] = [];
  }

  public function addPet($type, $name, $age)
  {
    $classMap = ['Dog' => Dog::class, 'Cat' => Cat::class, 'Bird' => Bird::class];
    if (isset($classMap[$type])) {
      $_SESSION['pets'][] = new $classMap[$type]($name, $age);
    }
  }

  public function getPets()
  {
    return $_SESSION['pets'];
  }
}

$shop = new PetShop();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $shop->addPet($_POST['type'], $_POST['name'], $_POST['age']);


  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

$pets = $shop->getPets();
?>



<!DOCTYPE html>
<html>

<head>
  <title>Simple Pet Shop</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <h1>Pet Shop</h1>
  <form method="post">
    <label>Type:
      <select name="type">
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
        <option value="Bird">Bird</option>
      </select>
    </label><br><br>

    <label>Name: <input name="name" required></label><br><br>
    <label>Age: <input name="age" type="number" required></label><br><br>

    <button type="submit">Add Pet</button>
  </form>

  <h2>Pet List</h2>
  <ul>
    <?php foreach ($pets as $pet): ?>
      <li><?= $pet->getInfo() ?> — <?= $pet->speak() ?></li>
    <?php endforeach; ?>
  </ul>
</body>

</html>