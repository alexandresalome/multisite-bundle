# Contributors

## By order of appearance

* Alexandre Salomé
* Julius Beckmann
* Cédric Blondeau
* Pascal Borreli
* Carlos Granados
* Sébastien Lévêque
* Sander Borgman

## Command used to generate:

```
git log --reverse --format="%aN" \
| sed "s/alexandresalome/Alexandre Salomé/g" \
| perl -ne 'if (!defined $x{$_}) { print $_; $x{$_} = 1; }'
```
