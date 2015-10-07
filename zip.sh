echo "Enter version number."
read version

mkdir ../../archives/sportspress-pro/$version

zip -r ../../archives/sportspress-pro/$version/sportspress-pro.agency.$version.zip . -x "*.DS_Store" ".tx/*" "*.sh"
zip -r ../../archives/sportspress-pro/$version/sportspress-pro.league.$version.zip . -x "*.DS_Store" ".tx/*" "*.sh" "includes/sportspress-agency/*"
zip -r ../../archives/sportspress-pro/$version/sportspress-pro.club.$version.zip . -x "*.DS_Store" ".tx/*" "*.sh" "includes/sportspress-agency/*" "includes/sportspress-multisite/*"
zip -r ../../archives/sportspress-pro/$version/sportspress-pro.social.$version.zip . -x "*.DS_Store" ".tx/*" "*.sh" "includes/sportspress-agency/*" "includes/sportspress-multisite/*" "includes/sportspress-lazy-loading/*" "includes/sportspress-sponsors/*" "includes/sportspress-staff-directories/*" "includes/sportspress-team-access/*" "includes/sportspress-tournaments/*"