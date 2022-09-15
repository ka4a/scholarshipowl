<?php namespace App\Services;

use Symfony\Component\HttpFoundation\File\File;

class DocumentGenerator
{
    const TYPE_PDF = "pdf";
    const TYPE_DOC = "doc";
    const TYPE_TXT = "txt";

    public static $typesNames = [
        self::TYPE_PDF => "PDF",
        self::TYPE_DOC => "DOC",
        self::TYPE_TXT => "TXT"
    ];

    /**
     * @param string $type
     * @param string $title
     * @param string $text
     *
     * @return File
     */
    public function generate(string $type, string $title, string $text)
    {
        switch ($type) {
            case self::TYPE_DOC:
                return static::generateWord2007($title, $text);
            case self::TYPE_PDF:
                return static::generatePDF($title, $text);
            case self::TYPE_TXT:
                return static::generateTXT($text);
            default:
                throw new \LogicException(sprintf('Unknown document type: %s', $type));
                break;
        }
    }

    /**
     * @param $title
     * @param $text
     *
     * @return File
     */
	public function generatePDF($title, $text)
    {
        $file = tmp_file('document_pdf');

		$text = nl2br($text);
		$content = "<h1>{$title}</h1><br><div>{$text}</div>";

		\PDF::loadHTML($content)->save($file);

        return $file;
	}

    /**
     * @param $title
     * @param $text
     *
     * @return File
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
	public function generateWord2007($title, $text)
    {
        $file = tmp_file('document_word_2007');
		$word = new \PhpOffice\PhpWord\PhpWord();

		$section = $word->addSection();
		$section->addText($title, array("name" => "Tahoma", "size" => 22, "bold" => true));
		$section->addText("", array("name" => "Tahoma", "size" => 14, "bold" => false));

		$lines = explode("\n", $text);
		foreach($lines as $num => $line) {
			$section->addText($line, array("name" => "Tahoma", "size" => 14, "bold" => false));
		}

		$writer = \PhpOffice\PhpWord\IOFactory::createWriter($word, "Word2007");
		$writer->save($file);

        return $file;
	}

    /**
     * @param $text
     *
     * @return File
     */
	public function generateTXT($text)
    {
		file_put_contents($file = tmp_file('document_txt'), $text);

        return $file;
	}
}

