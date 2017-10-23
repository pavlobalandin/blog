<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PostRepository extends EntityRepository
{
	/** @todo Move to global settings */
	const DEFAULT_RESULT_PER_PAGE = 5;

	/**
	 * @return Post[]
	 */
	public function findOrderedByDate()
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT p FROM AppBundle:Post p ORDER BY p.date DESC'
			)
			->getResult();
	}

	/**
	 * @param string $url
	 * @return mixed
	 */
	public function incrementViews($url)
	{
		return $this
			->createQueryBuilder('p')
			->update($this->getEntityName(), 'p')
			->set('p.views', 'p.views + 1')
			->where('p.url = :url AND p.isActive = 1')->setParameter('url', $url)
			->getQuery()
			->execute();
 	}

	/**
	 * @param int $id
	 * @return mixed
	 */
 	public function changeState($id)
	{
		return $this
			->createQueryBuilder('p')
			->update($this->getEntityName(), 'p')
			->set('p.isActive', '(CASE WHEN p.isActive = 1 THEN 0 ELSE 1 END)')
			->where('p.id = :id')->setParameter('id', $id)
			->getQuery()
			->execute();
	}

	/**
	 * @param int $pages
	 * @param int $startPage
	 * @param int $resultsPerPage
	 * @param bool $onlyActive
	 * @return Paginator
	 */
	public function findAllPaginated(&$pages, $startPage = 0, $resultsPerPage = self::DEFAULT_RESULT_PER_PAGE, $onlyActive = FALSE)
	{
		$dql = 'SELECT 
					p 
				FROM AppBundle:Post p '
			. ($onlyActive ? 'WHERE p.isActive = 1' : '')
			. ' ORDER BY p.date DESC';

		$query = $this->getEntityManager()->createQuery($dql)
			->setFirstResult($startPage * $resultsPerPage)
			->setMaxResults($resultsPerPage);

		$paginator = new Paginator($query);
		$count = $paginator->count();
		$pages = ceil($count/$resultsPerPage);

		return $paginator;
	}

	/**
	 * @param int $pages
	 * @param int $startPage
	 * @param int $resultsPerPage
	 * @return Paginator
	 */
	public function findAllActivePaginated(&$pages, $startPage = 0, $resultsPerPage = self::DEFAULT_RESULT_PER_PAGE)
	{
		return $this->findAllPaginated($pages, $startPage, $resultsPerPage, TRUE);
	}

	/**
	 * @return array
	 */
	public function findAllForApi()
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT 
						p.id, 
						p.title, 
						p.url, p.views 
					  FROM AppBundle:Post p 
					  WHERE p.isActive = 1 
					  ORDER BY p.date DESC'
			)
			->getResult();
	}
}