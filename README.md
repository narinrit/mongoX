# mongoX
Simple MongoDB php library

Connect to MongoDB

```
$db = new MongoX();
$db->connect('mongodb://localhost:27017/test');
$db->useDB('test');
```

FindOne
```
$user = self::$db->findOne('user', ['username' => 'test']);
```

Find
```
$post = self::$db->find('post', ['_id' => $id]);
```

Count
```
$count = self::$db->count('post', ['type' => 'text']);
```

Update
```
$update = $db->update('user', ['_id' => $id], ['$set' => ['username' => 'test']]);
```

Delete
```
$delete = $db->delete('post', ['_id' => $id]);
```
