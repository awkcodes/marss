## marss  
### the rss feed generator for static sites

usage :  

first go to config.php and edit the variables inside it to fit your publication
  


```
$ php marss.php http://link.publication file.html
```

if you want to make update on the same item check its guid and use it as a fourth param 
  
```
$ php marss.php http://link.publication file.html 2343459992
```


if you want automatic installation use install script in the folder , this will install the script
and the configs to ~/.local/bin/ if you want to change the directory where marss is installed, 
change it in the script.
