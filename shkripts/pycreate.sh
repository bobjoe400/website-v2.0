#!/bin/bash
source /var/www/bin/activate
inotifywait -q -m -e close_write  /var/www/pyscripts |
while read -r directory events filename; do
	if [[ $filename == *.ipynb ]];then
		jupyter nbconvert --to=script /var/www/pyscripts/"$filename"
	fi
done
