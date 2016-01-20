echo "Enter version number."
read version

zip -r ../../archives/sportspress-pro.$version.zip ../sportspress-pro -x "*.DS_Store" ".tx/*" "*.sh"