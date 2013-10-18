DROP TABLE IF EXISTS civicrm_event_sk;

CREATE TABLE civicrm_event_sk (
id INT NOT NULL AUTO_INCREMENT,
event_id INT NOT NULL ,
is_sk tinyint(4) default 0,
PRIMARY KEY(id)
);

/* Gegevens school */
SET @ufGId := '';

SELECT @ufGId := id FROM civicrm_uf_group WHERE name = 'Gegevens_School';

INSERT IGNORE INTO `civicrm_uf_group` ( `id`, `is_active`, `group_type`, `title`, `help_pre`, `help_post`, `limit_listings_group_id`, `post_URL`, `add_to_group_id`, `add_captcha`, `is_map`, `is_edit_link`, `is_uf_link`, `is_update_dupe`, `cancel_URL`, `is_cms_user`, `notify`, `is_reserved`, `name`, `created_id`, `created_date`, `is_proximity_search`) VALUES ( @ufGId, 1, 'Organization,Contact', 'Gegevens School', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, 'Gegevens_School', NULL, NULL, 0);

SET @ufGId := '';

SELECT @ufGId := id FROM civicrm_uf_group WHERE name = 'Gegevens_School';

SET @ufFFNId := '';
SET @ufFEId := '';
SET @ufFSAId := '';
SET @ufFCId := '';
SET @ufFPCId := '';

SELECT @ufFFNId := id FROM civicrm_uf_field WHERE field_name = 'organization_name' AND uf_group_id = @ufGId;
SELECT @ufFEId := id FROM civicrm_uf_field WHERE field_name = 'email' AND uf_group_id = @ufGId;
SELECT @ufFSAId := id FROM civicrm_uf_field WHERE field_name = 'street_address' AND uf_group_id = @ufGId;
SELECT @ufFCId := id FROM civicrm_uf_field WHERE field_name = 'city' AND uf_group_id = @ufGId;
SELECT @ufFPCId := id FROM civicrm_uf_field WHERE field_name = 'postal_code' AND uf_group_id = @ufGId;

INSERT IGNORE INTO `civicrm_uf_field` ( `id`, `uf_group_id`, `field_name`, `is_active`, `is_view`, `is_required`, `weight`, `help_post`, `help_pre`, `visibility`, `in_selector`, `is_searchable`, `location_type_id`, `phone_type_id`, `label`, `field_type`, `is_reserved`) VALUES ( @ufFFNId, @ufGId, 'organization_name', 1, 0, 1, 1, '', '', 'User and User Admin Only', 0, 0, NULL, NULL, 'Naam School', 'Organization', NULL);
INSERT IGNORE INTO `civicrm_uf_field` ( `id`, `uf_group_id`, `field_name`, `is_active`, `is_view`, `is_required`, `weight`, `help_post`, `help_pre`, `visibility`, `in_selector`, `is_searchable`, `location_type_id`, `phone_type_id`, `label`, `field_type`, `is_reserved`) VALUES ( @ufFEId, @ufGId, 'email', 1, 0, 1, 2, '', '', 'User and User Admin Only', 0, 0, NULL, NULL, 'Email', 'Contact', NULL);
INSERT IGNORE INTO `civicrm_uf_field` ( `id`, `uf_group_id`, `field_name`, `is_active`, `is_view`, `is_required`, `weight`, `help_post`, `help_pre`, `visibility`, `in_selector`, `is_searchable`, `location_type_id`, `phone_type_id`, `label`, `field_type`, `is_reserved`) VALUES ( @ufFSAId, @ufGId, 'street_address', 1, 0, 0, 3, '', '', 'User and User Admin Only', 0, 0, NULL, NULL, 'Addres', 'Contact', NULL);
INSERT IGNORE INTO `civicrm_uf_field` ( `id`, `uf_group_id`, `field_name`, `is_active`, `is_view`, `is_required`, `weight`, `help_post`, `help_pre`, `visibility`, `in_selector`, `is_searchable`, `location_type_id`, `phone_type_id`, `label`, `field_type`, `is_reserved`) VALUES ( @ufFCId, @ufGId, 'city', 1, 0, 0, 4, '', '', 'User and User Admin Only', 0, 0, NULL, NULL, 'City', 'Contact', NULL);
INSERT IGNORE INTO `civicrm_uf_field` ( `id`, `uf_group_id`, `field_name`, `is_active`, `is_view`, `is_required`, `weight`, `help_post`, `help_pre`, `visibility`, `in_selector`, `is_searchable`, `location_type_id`, `phone_type_id`, `label`, `field_type`, `is_reserved`) VALUES ( @ufFPCId, @ufGId, 'postal_code', 1, 0, 0, 5, '', '', 'User and User Admin Only', 0, 0, NULL, NULL, 'Postal Code', 'Contact', NULL);

SET @ufJId := '';

SELECT @ufJId := id FROM civicrm_uf_join WHERE module = 'Profile' AND uf_group_id = @ufGId;

INSERT IGNORE INTO `civicrm_uf_join` ( `id`, `is_active`, `module`, `entity_table`, `entity_id`, `weight`, `uf_group_id`) VALUES
( @ufJId, 1, 'Profile', NULL, NULL, @ufGId , @ufGId );

UPDATE `civicrm_uf_join` SET `weight` = weight+1 ORDER BY id DESC LIMIT 1 ;

