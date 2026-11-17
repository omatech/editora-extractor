<?php
use Doctrine\DBAL\DriverManager;
use GraphQL\Language\Parser;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\GraphQLPreprocessor;
use Omatech\Editora\Extractor\Ferretizer;
use PHPUnit\Framework\TestCase;

final class ExtractionTest extends TestCase
{
    public function test_lookup_values_are_literal_ids_and_never_predicates(): void
    {
        $db = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
        $db->executeStatement('CREATE TABLE omp_lookups_values(id INTEGER, value TEXT)');
        $db->insert('omp_lookups_values', ['id' => 7, 'value' => 'visible']);
        $db->insert('omp_lookups_values', ['id' => 9, 'value' => 'foreign']);
        EditoraData::set_connection($db);
        self::assertSame('visible', EditoraData::get_lookup_value(7));
        self::assertNull(EditoraData::get_lookup_value(99));
        self::assertNull(EditoraData::get_lookup_value('99 OR 1=1'));
        $db->close();
    }

    public function test_generated_graphql_remains_parseable_with_relations_and_null_values(): void
    {
        $query = ['type' => 'instance', 'fields' => 'title', 'relations' => ['children' => ['fields' => 'title']]];
        self::assertNotEmpty(Parser::parse(GraphQLPreprocessor::generate($query))->definitions);
        self::assertStringContainsString('all_values_even_null', GraphQLPreprocessor::generate($query, true));
    }

    public function test_ferretizer_rejects_an_incomplete_instance_without_php_diagnostics(): void
    {
        self::assertNull(Ferretizer::FerretizeInstance(['id' => 7]));
        $result = Ferretizer::FerretizeInstance(['id' => 7, 'class_id' => 3, 'link' => '/ca/page', 'all_values' => [['atri_tag' => 'title', 'text_val' => 'Àccés', 'num_val' => null]]]);
        self::assertSame(7, $result['id']);
        self::assertSame('/ca/page', $result['link']);
        self::assertSame('Àccés', $result['title']);
    }
}
