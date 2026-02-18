CREATE TABLE tx_faq_domain_model_faq (
    question varchar(255) NOT NULL DEFAULT '',
    answer text,
    sorting int(11) NOT NULL DEFAULT '0',
    categories int(11) NOT NULL DEFAULT '0',
);
