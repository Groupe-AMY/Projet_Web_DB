/*
 Description :  Reset the quantity of the snows
 Author :       yannick.BAUDRAZ@cpnv.ch
 Creation :		2019.05.24
 Version :      2019.05.25
 */

USE snows;

UPDATE snows SET qtyAvailable = 22 WHERE id = 1;
UPDATE snows SET qtyAvailable = 2 WHERE id = 2;
UPDATE snows SET qtyAvailable = 6 WHERE id = 3;
UPDATE snows SET qtyAvailable = 2 WHERE id = 4;
UPDATE snows SET qtyAvailable = 11 WHERE id = 5;
UPDATE snows SET qtyAvailable = 26 WHERE id = 6;
UPDATE snows SET qtyAvailable = 9 WHERE id = 7;
UPDATE snows SET qtyAvailable = 1 WHERE id = 8;
UPDATE snows SET qtyAvailable = 15 WHERE id = 9;
