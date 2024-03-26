# Trickster Web
![](https://forum.ragezone.com/attachments/sar27by-png.257629/)

# First Steps
1. Create a new Database named "web";
2. Run the .sql files located in the "Queryes" folder;
3. Edit the "inc/config.php" file to your specifications;  

# Web Database Table Functions
### Basic Functions
- **web_user**: Master Accounts (if enabled);
- **web_user_char**: Game Accounts associated with the Master Accounts (if enabled);
- **web_slider**: Sliders (homepage);
- **web_news**: News posts, for the news system;
- **web_sell**: List of items that are avaliable for purchase inside MyShop;
- **web_admin**: List of Master Accounts (Or Game Accounts) that can manage the News and Slider systems

### Daily Rewards
- **game_prizes**: List of prizes, avaliable in the Daily Rewards;
- **game_play_log**: Log for the Daily Reward page;

### Other
- **user_email**: Holds all Game Accounts email addresses (if Master Account is not enabled);
- **user_point**: Holds all Game Accounts MyShop balance (if Master Account is not enabled);
