Li3Models
=========

This is an attempt to make Lithium's model layer to be accessible in virtually any framework, or even in projects using flat PHP.

##Introduction

Working in Lithium has been really inspiring and yes, this framewokr is definitely for people who want freedom and want their applications to grow out into it's own framework.

One of the core things that makes Lithium so great is it's Model layer. It's out of the box support for MongoDB, and of course, aspect oriented programming via filters :)

In the past couple of months I had to take on projects both in CodeIgniter and CakePHP. I like both of these frameworks as they offer their own unique qualities but I will be honest here and say that yes, at the moment I *love* Lithium ! and I was really missing the power and efficiency of it's model layer. Most notably in my CodeIgniter project, where I dropped the idea of using MongoDB mainly because I just did not want to deal with  tons of MongoDB libraries CodeIgniter out there.

So behold ! ... now you can fork this project and use Lithium framework's model layer in any PHP framework. And yes, even flat PHP [ of course as long as you are working in PHP 5.3+ ;) ]

##Usage

First clone this repository the standard way using "git clone".

Then, simply

	git submodule init
	git submodule update

to downloand the Lithium framework as a submodule.

After this, edit Li3Models/config/connections.php and add your connection detail as you would for any Lithium project, as says in the guide here:

	[The Manual](http://lithify.me/docs/manual/working-with-data/using-data-sources.wiki)
	[The Method](http://lithify.me/docs/lithium/data/Connections::add())


Once you are done setting up the connection, you can start writing models for your project in the directory:

	Li3Models/models

I have included example usage in:

	Li3Models/test.php

All you need to do is first add Li3Models.php by simply:

	require PATH_TO_Li3Models . '/Li3Models.php';

and viola ! now not only can you use your models directly like this:

	use Li3Models\models\Users;

	$data = Users::first()->to('array');
	print_r($data->to('array'));

You can also use Li3Models class like this:

	$data = Li3Models::run('Users', 'first');
	print_r($data->to(array));


##IMPORTANT NOTE:

If you wish to use the `Li3Models::run()` method, then please make use your models inherit the `AppModel` class here:

	Li3Models/extensions/data/AppModel.php

	like: Class Users extends \Li3Models\extensions\data\AppModel

And we're done !

Please do post any questions / issues would be more than happy to help and keep sharing the goodness folks :)
