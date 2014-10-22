for file in $(find ./core/sportspress/languages/ -name *.po -type f);
	do msgfmt "$file" -o "${file%po}mo"; rm "$file";
done

for file in $(find ./languages/ -name *.po -type f);
	do msgfmt "$file" -o "${file%po}mo"; rm "$file";
done