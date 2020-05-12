# SL-Marketplace-Dashboard
A Web based (PHP, MySQL) Dashboard for Second Life MP Sales.

A system that will combine and display the data from Marketplace Orders on one dashboard webpage.  This is done by uploading the orders CSV file obtained from the Second Life Marketplace.  This file is located on the Merchant Home, under "Reports" then "Orders".  You will use the regular CSV and not the one labeled "Txn CSV"  

# Set Up

* Place the files in a directory on your webserver.  You will also need a writable directory called "upload" in the same folder.

* Upload the SQL scheme to a database on your webserver to create the tables needed.  You should also create a user to access the data.

* In this current iteration you will need to manually add at least one store to the Stores table.  Future versions will include a form to add stores.  You may add as many stores as you like if you have more than one.

* Configure the db.php file with the information for the database and database user you just created.

* Access the index.php file you placed on your server.  If everything is configured correctly, you should see a box to upload your CSV file.

# Use

* Obtain your store's CSV file, Select the desired Date range then download the file.  

* Select your CSV file and the approproate store.  Then upload.

* Upon refresh, you will get several tabs with 50 entries per page (This will be made configurable in a future update)

* If you upload multiple CSVs for different stores, the transactions will order by date.  You will also get a listing of total sales on the home page and an approximate USD value (based on 1L$=$2.50 USD)

# Customizing

* If you are knowledgeable in PHP and HTML you can customize the look fairly easily.  Most of the code is in the index.php file, mostly for simplicity in distribution.  If you wish to change the currency to something besides USD, for example you could easily edit the code that does the conversion, since it's simply a quick division problem stuck in a variable.  I have mine hosted inside a web framework, so the form URL is "index.php?page=##" instead of "index.php", for example.  The code still works fine.






