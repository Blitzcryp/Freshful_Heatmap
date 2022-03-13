<?php


namespace App\Services\User;


use App\Repositories\User\GetUserJourneyRepository;
use App\Repositories\User\UsersRepository;
use Illuminate\Contracts\Validation\Factory;

class GetUsersSimilarJourneyService
{
    private GetUserJourneyRepository $getUserJourneyRepository;
    private Factory $validator;
    private UsersRepository $usersRepository;

    public function __construct(
        GetUserJourneyRepository $getUserJourneyRepository,
        Factory $validator,
        UsersRepository $usersRepository
    ){
        $this->getUserJourneyRepository = $getUserJourneyRepository;
        $this->validator = $validator;
        $this->usersRepository = $usersRepository;
    }

    public function get(int $id){
        ["id" => $id] = $this->validate($id);

        $userJourney = $this->getUserJourneyRepository->get($id);
        $userJourney->map( function($value) {
            return (object)["link" => $value->link, "timestamp" => $value->timestamp];
        });

        $uniqueLinks = $userJourney->map( function($value) {
            return $value->link;
        })->unique()->toArray();

        $similarLinks = $this->getUserJourneyRepository->getOtherUsersJourney($id, $uniqueLinks);

        $usersPartitions = [];
        $similarLinks->map(function($value) use (&$usersPartitions) {
            $usersPartitions[$value->uid][] = (object)["link" => $value->link, "timestamp" => $value->timestamp];
        });
        $userJourney->map(function($value) use(&$usersPartitions){
            $usersPartitions[$value->uid][] = (object)["link" => $value->link, "timestamp" => $value->timestamp];
        });

        $paths = [];
        foreach($usersPartitions as $id => $usersPartition){
            $paths[$id] = $this->createJourneyForUser($usersPartition);
        }


        $myUserPath = $paths[$id];

        $answer = [];
        foreach($paths as $uid => $path){
            if($uid !== $id && $path === $myUserPath){
                array_push($answer, $uid);
            }
        }

        return ["users" => $answer];
    }

    private function createJourneyForUser(array $userJourney): string
    {
        $journey = "";
        foreach($userJourney as $fragment){
            $journey .= $fragment->link . ": warp :";
        }

        return $journey;
    }

    private function validate(int $id): array
    {
        return $this->validator->make(
            ["id" => $id],
            ["id" => function (string $attribute, int $value, callable $fail) {
                if($value < 0){
                    $fail("positive");
                }

                if(!$this->usersRepository->userExistsById($value)){
                    $fail("user does not exist");
                }
            }],
            [
                "id.required" => "required",
                "id.numeric" => "numeric",
            ]
        )->validate();
    }
}
