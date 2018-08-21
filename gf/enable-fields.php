<?php
// ENABLE OUR CREDIT CARD FIELDS
add_action("gform_enable_credit_card_field", "spark_enable_credit_card_field");
function spark_enable_credit_card_field($is_enabled) {
    return true;
}

// ENABLE PASSWORD FIELDS
add_action("gform_enable_password_field", "spark_enable_password_field");
function spark_enable_password_field($is_enabled) {
    return true;
}
