tx pull -a;

for file in $(find ./includes/sportspress/languages/ -name *.po -type f);
	do msgfmt "$file" -o "${file%po}mo"; rm "$file";
done
rm "includes/sportspress/languages/sportspress-en_US.mo";

for file in $(find ./languages/ -name *.po -type f);
	do msgfmt "$file" -o "${file%po}mo"; rm "$file";
done
rm "languages/sportspress-pro-en_US.mo";