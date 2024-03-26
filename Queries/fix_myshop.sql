USE [trickster]
GO

ALTER TABLE tbl_charged_item
DROP CONSTRAINT fk_tbl_charged_item_take_char;

ALTER TABLE tbl_charged_item
DROP CONSTRAINT fk_tbl_charged_item_user_uid;