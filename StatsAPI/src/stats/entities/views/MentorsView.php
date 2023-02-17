<?php


namespace stats\entities\views;


use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use stats\controllers\MentorsManager;
use stats\entities\mentors\ActiveMentor;
use stats\Loader;

class MentorsView extends BaseView
{


	public function createForm(Player $player)
	{
		$mentors = $this->getMentors($player);

		if (count($mentors) == 0) {
			$player->sendMessage("§cNão tem nenhum mentor disponivel para o seu uso!");
			return;
		}

		$form = new SimpleForm(function (Player $player, $data) use ($mentors){
			if(!is_null($data) || is_numeric($data)){
				if(isset($mentors[$data])){
					$mentor = MentorsManager::getMentorByName($mentors[$data]);
					$view = new ActiveMentorView($mentor);
					$view->sendForm($player);
				}
			}
		});

		$form->setTitle("Mentores");
		$form->setContent("§e* Ative um mentor para ganhar stats!");
		foreach ($mentors as $mentorName){
			$mentor = MentorsManager::getMentorByName($mentorName);
		  if (!is_null($mentor)) {	  $form->addButton($mentor->getName(true) . "\n(Clique para ativar)");
      }
		}
		$this->form = $form;
	}

	/**
	 * @param Player $player
	 * @return string[]
	 */
	private function getMentors(Player $player): array
	{
		$mentors = [];
		foreach (MentorsManager::getMentors() as $name => $mentor) {
			if ($player->hasPermission($mentor->getPermission())) {
				$mentors[] = $name;
			}
		}
		return $mentors;
	}


}