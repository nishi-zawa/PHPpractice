<?php

define("MSGKEY_EMPID", "社員ID");
define("MSGKEY_EMPFN", "社員名（姓）");
define("MSGKEY_EMPLN", "社員名（名）");
define("MSGKEY_EMPSEC", "所属セクション");
define("MSGKEY_EMPMAIL", "メールアドレス");
define("MSGKEY_EMPGENDER", "性別");

define("DIGITS_EMPID", 10);

define("MAX_EMPFN", 20);
define("MAX_EMPLN", 20);
define("MAX_EMPMAIL", 256);

define("REGEX_EMPID", "/^YZ[0-9]{8}$/");
define("REGEX_EMPSEC", "/[123]{1}$/");
define("REGEX_EMPMAIL", "/^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9._-]{1,}$/");
define("REGEX_EMPGENDER", "/[12]{1}$/");


?>