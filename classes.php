<?php
require_once "app/config/Parameters.php";

require_once "src/Model/Person.php";
require_once "src/Model/Language.php";

require_once "src/Database/DatabaseManager.php";
require_once "src/Database/JsonDatabaseManager.php";
require_once "src/Database/Exception/UnsupportedEntity.php";
require_once "src/Database/Exception/LanguageAlreadyExists.php";
require_once "src/Database/Exception/LanguageNotFound.php";
require_once "src/Database/Exception/LanguageUsed.php";

require_once "src/Repository/Languages.php";
require_once "src/Repository/LanguagesRepository.php";
require_once "src/Repository/Persons.php";
require_once "src/Repository/PersonsRepository.php";

require_once "src/Application/Application.php";
require_once "src/Application/CommandHandler.php";
require_once "src/Application/Validator.php";
require_once "src/Application/Exception/NotFoundException.php";
require_once "src/Application/Exception/ValidationException.php";
