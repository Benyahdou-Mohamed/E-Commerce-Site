namespace App\Models\Category; 


class AbstractCategory {
    protected int $id;
    protected string $name;

    public function __construct(array $data){
        $this->id = (int) $data["id"];
        $this->name = $data["name"];
    }
    public function getId():int{
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getArray():array 
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
        ]
    }


}