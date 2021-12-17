<?php


namespace maesierra\AdventOfCode2021\Day15;


/**
 * @author    Greg Roach <greg@subaqua.co.uk>, Maria-Eugenia Sierra
 * @copyright (c) 2021 Greg Roach <greg@subaqua.co.uk>
 * @license   GPL-3.0+
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses>.
 */

/**
 * Class Dijkstra - Use Dijkstra's algorithm to calculate the shortest path
 * through a weighted, directed graph.
 */
class Dijkstra
{
    /** @var integer[][] The graph, where $graph[node1][node2]=cost */
    protected $graph;

    /** @var int[] Distances from the source node to each other node */
    protected $distance;

    /** @var string[][] The previous node(s) in the path to the current node */
    protected $previous;

    /** @var int[] Nodes which have yet to be processed */
    protected $queue;

    /**
     * @param integer[][] $graph
     */
    public function __construct($graph)
    {
        $this->graph = $graph;
    }

    /**
     * Process the next (i.e. closest) entry in the queue.
     *
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return void
     */
    protected function processNextNodeInQueue(array $exclude)
    {
        // Process the closest vertex
        $closest = array_search(min($this->queue), $this->queue);
        if (!empty($this->graph[$closest]) && !in_array($closest, $exclude)) {
            foreach ($this->graph[$closest] as $neighbor => $cost) {
                if (isset($this->distance[$neighbor])) {
                    if ($this->distance[$closest] + $cost < $this->distance[$neighbor]) {
                        // A shorter path was found
                        $this->distance[$neighbor] = $this->distance[$closest] + $cost;
                        $this->previous[$neighbor] = array($closest);
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    } elseif ($this->distance[$closest] + $cost === $this->distance[$neighbor]) {
                        // An equally short path was found
                        $this->previous[$neighbor][] = $closest;
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    }
                }
            }
        }
        unset($this->queue[$closest]);
    }

    /**
     * Extract all the paths from $source to $target as arrays of nodes.
     *
     * @param string $target The starting node (working backwards)
     *
     * @return string[] One of the shortest paths, represented by a list of nodes
     */
    protected function extractPath($target) {
        $path = [$target];
        $next = reset($this->previous[$path[0]]);
        do {
            $path[] = $next;
            $next = reset($this->previous[$next]);
        } while ($next);
        return $path;
    }

    /**
     * Calculate the shortest path through a a graph, from $source to $target.
     *
     * @param string   $source  The starting node
     * @param string   $target  The ending node
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return string[] Zero or more shortest paths, each represented by a list of nodes
     */
    public function shortestPath($source, $target, array $exclude = array())
    {
        // The shortest distance to all nodes starts with infinity...
        $this->distance = array_fill_keys(array_keys($this->graph), INF);
        // ...except the start node
        $this->distance[$source] = 0;

        // The previously visited nodes
        $this->previous = array_fill_keys(array_keys($this->graph), array());

        // Process all nodes in order
        $this->queue = array($source => 0);
        while (!empty($this->queue)) {
            $this->processNextNodeInQueue($exclude);
        }
        if ($source === $target) {
            // A null path
            return [$source];
        } elseif (empty($this->previous[$target])) {
            // No path between $source and $target
            return [];
        } else {
            // One or more paths were found between $source and $target
            return $this->extractPath($target);
        }
    }
}