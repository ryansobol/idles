<?php

namespace Idles;

/**
 * Maps and removes null values from the result.
 * 
 * @template T
 * @template U
 * @param callable(T $value, array-key $key, iterable<array-key,T> $collection):(U|null) $iteratee
 * @param ?iterable<array-key,T> $collection
 * @return \Closure|list<U>
 * 
 * @example ```
 *   compactMap(fn ($n) => $n > 2 ? $n * $n : null, [1, 2, 3]); // [9]
 * ```
 * 
 * @category Collection
 * 
 * @see map()
 * @see filter()
 * 
 * @idles-lazy
 * @idles-reindexed
 */
function compactMap(mixed ...$args)
{
    static $arity = 2;
    return curryN($arity,
        function (callable $iteratee, ?iterable $collection) {
            $collection ??= [];

            if (\is_object($collection) && \is_a($collection, '\Iterator')) {
                return new Iterators\ValuesIterator(
                    new \CallbackFilterIterator(
                        _map($collection, $iteratee),
                        fn ($v) => $v !== null
                    )
                );
            }

            return \array_values(
                \array_filter(
                    _map($collection, $iteratee),
                    fn ($v) => $v !== null
                )
            );
        }
    )(...$args);
}
