SELECT `tx_faq_domain_model_faq`.`*`
FROM `tx_faq_domain_model_faq`
         LEFT JOIN `sys_category_record_mm`
                   ON (
                       `tx_faq_domain_model_faq`.`uid` = `sys_category_record_mm`.`uid_foreign`
                           AND `sys_category_record_mm`.`tablenames` = 'tx_faq_domain_model_faq'
                           AND `sys_category_record_mm`.`fieldname` = 'categories'
                       )
         LEFT JOIN `sys_category`
                   ON `sys_category_record_mm`.`uid_local` = `sys_category`.`uid`
WHERE (
          `tx_faq_domain_model_faq`.`pid` IN (24, 31, 30, 29, 28, 27, 26, 25, 32)
              AND `sys_category`.`uid` IN (10, 2)
              AND `tx_faq_domain_model_faq`.`sys_language_uid` IN (0, -1)
              AND `tx_faq_domain_model_faq`.`deleted` = 0
              AND `tx_faq_domain_model_faq`.`hidden` = 0
              AND (
              (`sys_category`.`deleted` = 0
                  AND `sys_category`.`t3ver_state` <= 0
                  AND `sys_category`.`t3ver_wsid` = 0
                  AND (`sys_category`.`t3ver_oid` = 0 OR `sys_category`.`t3ver_state` = 4)
                  AND `sys_category`.`hidden` = 0
                  AND `sys_category`.`starttime` <= 1771414860
                  AND (`sys_category`.`endtime` = 0 OR `sys_category`.`endtime` > 1771414860)
                  )
                  OR `sys_category`.`uid` IS NULL
              )
          )
ORDER BY `tx_faq_domain_model_faq`.`sorting` ASC;
