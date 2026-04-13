<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed', description: 'Seed catalogue démo (jeux/console/offres).')]
class SeedDemoDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CategoryRepository $categoryRepository,
        private readonly ProductTypeRepository $productTypeRepository,
        private readonly ProductRepository $productRepository,
        private readonly OrderRepository $orderRepository,
        private readonly Slug $slugger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('reset', null, InputOption::VALUE_NONE, 'Supprime les données catalogue/commandes avant de reseed.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ((bool) $input->getOption('reset')) {
            $this->resetDemoData($output);
        }

        $categories = [
            ['slug' => 'jeux', 'name' => 'Jeux'],
            ['slug' => 'consoles', 'name' => 'Consoles'],
            ['slug' => 'offres', 'name' => 'Offres'],
        ];

        $types = [
            'Nouveau',
            'Promotion',
            'Standard',
        ];

        $catEntities = [];
        foreach ($categories as $c) {
            $existing = $this->categoryRepository->findOneBy(['slug' => $c['slug']]);
            if ($existing) {
                $existing->setName($c['name'])->setSlug($c['slug']);
                $catEntities[$c['slug']] = $existing;
                continue;
            }
            $cat = (new Category())->setName($c['name'])->setSlug($c['slug']);
            $this->em->persist($cat);
            $catEntities[$c['slug']] = $cat;
        }

        $typeEntities = [];
        foreach ($types as $name) {
            $existing = $this->productTypeRepository->findOneBy(['name' => $name]);
            if ($existing) {
                $typeEntities[$name] = $existing;
                continue;
            }
            $t = (new ProductType())->setName($name);
            $this->em->persist($t);
            $typeEntities[$name] = $t;
        }

        $products = [
            // Jeux (6)
            ['slug' => 'neon-drift-ps5', 'name' => 'Neon Drift (PS5)', 'desc' => 'Course arcade futuriste.', 'price' => 5999, 'cat' => 'jeux', 'type' => 'Nouveau', 'img' => '/assets/products/game-neon.svg'],
            ['slug' => 'skyforge-quest-pc', 'name' => 'Skyforge Quest (PC)', 'desc' => 'RPG coop en ligne.', 'price' => 3999, 'cat' => 'jeux', 'type' => 'Standard', 'img' => '/assets/products/game-sky.svg'],
            ['slug' => 'shadow-ops-xbox', 'name' => 'Shadow Ops (Xbox)', 'desc' => 'FPS tactique.', 'price' => 4999, 'cat' => 'jeux', 'type' => 'Promotion', 'img' => '/assets/products/game-ops.svg'],
            ['slug' => 'mythic-valley-switch', 'name' => 'Mythic Valley (Switch)', 'desc' => 'Aventure et crafting.', 'price' => 4499, 'cat' => 'jeux', 'type' => 'Standard', 'img' => '/assets/products/game-valley.svg'],
            ['slug' => 'galaxy-raiders-pc', 'name' => 'Galaxy Raiders (PC)', 'desc' => 'Shooter spatial.', 'price' => 2999, 'cat' => 'jeux', 'type' => 'Promotion', 'img' => '/assets/products/game-galaxy.svg'],
            ['slug' => 'summit-legends-ps5', 'name' => 'Summit Legends (PS5)', 'desc' => 'Survie en montagne.', 'price' => 4999, 'cat' => 'jeux', 'type' => 'Nouveau', 'img' => '/assets/products/game-mountain.svg'],

            // Consoles (2)
            ['slug' => 'console-nova-x', 'name' => 'Console Nova X', 'desc' => 'Console nouvelle génération.', 'price' => 49999, 'cat' => 'consoles', 'type' => 'Standard', 'img' => '/assets/products/console.svg'],
            ['slug' => 'console-mini-retro', 'name' => 'Console Mini Retro', 'desc' => 'Pack rétro + 30 classiques.', 'price' => 8999, 'cat' => 'consoles', 'type' => 'Promotion', 'img' => '/assets/products/console-mini.svg'],

            // Offres (4)
            ['slug' => 'psplus-1m', 'name' => 'PS Plus — 1 mois', 'desc' => 'Accès en ligne + jeux mensuels.', 'price' => 1099, 'cat' => 'offres', 'type' => 'Standard', 'img' => '/assets/products/sub-1.svg'],
            ['slug' => 'psplus-3m', 'name' => 'PS Plus — 3 mois', 'desc' => 'Accès en ligne + jeux mensuels.', 'price' => 2999, 'cat' => 'offres', 'type' => 'Promotion', 'img' => '/assets/products/sub-3.svg'],
            ['slug' => 'psplus-6m', 'name' => 'PS Plus — 6 mois', 'desc' => 'Accès en ligne + jeux mensuels.', 'price' => 5499, 'cat' => 'offres', 'type' => 'Standard', 'img' => '/assets/products/sub-6.svg'],
            ['slug' => 'psplus-12m', 'name' => 'PS Plus — 12 mois', 'desc' => 'Accès en ligne + jeux mensuels.', 'price' => 7999, 'cat' => 'offres', 'type' => 'Promotion', 'img' => '/assets/products/sub-12.svg'],
        ];

        foreach ($products as $p) {
            $existing = $this->productRepository->findOneBy(['slug' => $p['slug']]);
            $prod = $existing ?? new Product();
            $prod
                ->setName($p['name'])
                ->setSlug($p['slug'])
                ->setDescription($p['desc'])
                ->setPriceCents($p['price'])
                ->setImagePath($p['img'])
                ->setCategory($catEntities[$p['cat']])
                ->setType($typeEntities[$p['type']]);
            if (!$existing) $this->em->persist($prod);
        }

        $this->em->flush();
        $output->writeln('<info>Données de démo ajoutées / mises à jour.</info>');
        return Command::SUCCESS;
    }

    private function resetDemoData(OutputInterface $output): void
    {
        $output->writeln('<comment>Reset des données de démo…</comment>');

        foreach ($this->orderRepository->findAll() as $order) {
            $this->em->remove($order);
        }
        foreach ($this->productRepository->findAll() as $product) {
            $this->em->remove($product);
        }
        foreach ($this->categoryRepository->findAll() as $category) {
            $this->em->remove($category);
        }
        foreach ($this->productTypeRepository->findAll() as $type) {
            $this->em->remove($type);
        }

        $this->em->flush();
        $output->writeln('<info>Reset terminé.</info>');
    }
}

