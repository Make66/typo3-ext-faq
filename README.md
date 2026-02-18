# FAQ Extension for TYPO3

## User Story

As a **TYPO3 editor**, you want to create FAQ entries and organize them into categories so that website visitors can browse frequently asked questions in a clean, collapsible accordion — grouped by topic, with each category and question expandable on click.

You select one or more storage pages and a parent category in the plugin's FlexForm. The extension then displays all FAQ records from those pages whose categories are children of the selected parent, rendered as a nested Bootstrap 5 accordion — outer level per category, inner level per question — all collapsed by default.

Install as usual, preferably by Composer and add the Site Set of Taketool/FAQ to your Site Configuration.

## Technical summary:

Here's a summary of the complete TYPO3 13.4 FAQ extension:                                                                                                                                                                                               

### Core files:                                                                                                                                                                                                                                                                           
  - composer.json — PSR-4 autoload Taketool\Faq\ → Classes/, requires typo3/cms-core ^13.4                                                                                                                                                                                              
  - ext_emconf.php — TYPO3 13.4 constraints                                                                                                                                                                                                                                             
  - ext_localconf.php — Registers FaqList plugin as content element                                                                                                                                                                                                                     
  - ext_tables.sql — Table definition for tx_faq_domain_model_faq                                                                                                                                                                                                                       

  ### Domain layer:
  - Classes/Domain/Model/Faq.php — Entity with question, answer, sorting, categories (ObjectStorage)
  - Classes/Domain/Repository/FaqRepository.php — findByPagesAndCategory() fetches FAQs from selected pages, filters by child categories of the parent, groups results by category
  - Classes/Controller/FaqController.php — Reads FlexForm settings, passes grouped FAQs to view

### Configuration:
  - Configuration/TCA/tx_faq_domain_model_faq.php — Full TCA with RTE for answer, type: category for categories, language support
  - Configuration/TCA/Overrides/tt_content.php — Plugin registration + FlexForm binding
  - Configuration/FlexForms/FaqList.xml — Page selector (group type) + parent category selector
  - Configuration/Extbase/Persistence/Classes.php — Model-to-table mapping
  - Configuration/Services.yaml — Autowiring for Taketool\Faq\ classes
  - Configuration/Icons.php — SVG icon registration

### Frontend:
  - Resources/Private/Templates/Faq/List.html — Bootstrap 5 accordion: outer loop per category, inner loop per FAQ, all collapsed by default
  - Resources/Private/Partials/Faq/AccordionItem.html — Individual FAQ accordion item with f:format.html for RTE content

### Language files:
  - locallang.xlf — Plugin labels, FlexForm labels
  - locallang_db.xlf — TCA field labels
