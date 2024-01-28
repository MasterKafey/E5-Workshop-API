<?php

namespace App\Command\User;

use App\Entity\User;
use App\Form\Type\Authentication\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\FormFactoryInterface;

#[AsCommand(name: 'app:user:create', description: 'Create a new user')]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory,
    )
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, "User's email")
            ->addArgument('password', InputArgument::OPTIONAL, "User's password");
    }

    public function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->ask('email', $input, $output);
        $this->ask('password', $input, $output, true);
    }

    public function ask(string $argumentName, InputInterface $input, OutputInterface $output, bool $hideInput = false): string
    {
        if (empty($input->getArgument($argumentName))) {

            $question = new Question("$argumentName: ");
            $question->setHidden($hideInput);
            $helper = $this->getHelper('question');

            $input->setArgument($argumentName, $helper->ask($input, $output, $question));
        }

        return $input->getArgument($argumentName);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $form = $this->formFactory->create(RegisterType::class, $user)->submit([
            'email' => $input->getArgument('email'),
            'plainPassword' => $input->getArgument('password'),
        ]);

        if (!$form->isSubmitted() || !$form->isValid()) {
            foreach($form->getErrors(true) as $error) {
                $output->writeln("<error>{$error->getMessage()}</error>");
            }

            return Command::FAILURE;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        $output->writeln("<info>{$user->getEmail()} created with success</info>");

        return Command::SUCCESS;
    }
}