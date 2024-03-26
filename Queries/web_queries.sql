USE [web]
GO
/****** Object:  Table [dbo].[game_play_log]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[game_play_log](
	[uid] [int] NOT NULL,
	[item_id] [int] NOT NULL,
	[item_date] [datetime] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[game_prizes]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[game_prizes](
	[item_id] [int] NOT NULL,
	[amount] [int] NOT NULL,
	[rate] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[user_email]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[user_email](
	[uid] [int] NOT NULL,
	[email] [nvarchar](50) NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[user_point]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[user_point](
	[uid] [int] NOT NULL,
	[points] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[web_admin]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[web_admin](
	[uid] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[web_news]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[web_news](
	[nid] [int] IDENTITY(1,1) NOT NULL,
	[Title] [nvarchar](200) NOT NULL,
	[Content] [text] NOT NULL,
	[Date] [datetime] NOT NULL,
	[Author] [nvarchar](50) NOT NULL,
	[Thumbnail] [text] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[web_sell]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[web_sell](
	[item_id] [int] NOT NULL,
	[purchaseable] [bit] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[web_slider]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[web_slider](
	[sid] [int] IDENTITY(1,1) NOT NULL,
	[Image] [text] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[web_user]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[web_user](
	[uid] [int] IDENTITY(1,1) NOT NULL,
	[Login] [nvarchar](50) NOT NULL,
	[Password] [nvarchar](50) NOT NULL,
	[Email] [nvarchar](50) NOT NULL,
	[Points] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[web_user_char]    Script Date: 26/03/2024 13:26:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[web_user_char](
	[uid] [int] NOT NULL,
	[char_uid] [int] NOT NULL
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[game_play_log] ADD  CONSTRAINT [DF_Table_1_Date]  DEFAULT (getdate()) FOR [item_date]
GO
ALTER TABLE [dbo].[web_news] ADD  CONSTRAINT [DF_web_news_Date]  DEFAULT (getdate()) FOR [Date]
GO
ALTER TABLE [dbo].[web_sell] ADD  CONSTRAINT [DF_web_sell_purchaseable]  DEFAULT ((0)) FOR [purchaseable]
GO
ALTER TABLE [dbo].[web_user] ADD  CONSTRAINT [DF_web_user_Points]  DEFAULT ((0)) FOR [Points]
GO
