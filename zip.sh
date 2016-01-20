echo "Enter version number."
read version

mkdir ../../archives/sportspress-pro/$version

zip -r ../../archives/sportspress-pro/$version/sportspress-pro.zip . -x "*.DS_Store" ".tx/*" "*.sh"