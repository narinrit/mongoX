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
$user = self::$db->findOne('user', [
					'username' => $username
				]);
```

Find
```
$post = self::$db->find('post', [
					'_id' => $id
				]);
```

Update
```
self::$db->update('user', [
				'_id' => $id
			], [
				'$set' => [
          'username' => $username
        ]
			]);
```

Delete
```
self::$db->delete('post', [
				'_id' => $id
			]);
```
