"use client";

import { SearchBox, ResultsBox } from "@/components";
import { SEARCH_CATEGORIES } from "@/constants";
import { useResultsStore } from "@/stores";
import { useSearch } from "@/hooks";
import { useCallback } from "react";

const Home = () => {
  const { isLoading, refetch, fetchMore } = useSearch();
  const { results, setResults, setCurrentPage } = useResultsStore();

  const handleSearch = () => {
    setCurrentPage(1);
    refetch();
  };

  const handleViewMore = useCallback(async () => {
    const { data: moreData, nextPage } = await fetchMore();
    if (moreData) {
      setCurrentPage(nextPage);
      setResults({
        ...moreData,
        results: [...results.results, ...moreData.results],
      });
    }
  }, [fetchMore, results.results, setCurrentPage, setResults]);

  return (
    <div className="grid grid-cols-7 grid-rows-6 gap-5 w-full mb-10 px-5 md:w-3/4 md:mx-auto">
      <div className="col-span-7 row-span-2 md:col-span-3 box-border">
        <SearchBox
          onSearch={handleSearch}
          options={SEARCH_CATEGORIES}
          isSearching={isLoading}
        />
      </div>
      <div className="col-span-7 row-span-6 md:col-span-4 box-border">
        <ResultsBox
          results={results}
          isSearching={isLoading}
          onViewMore={handleViewMore}
        />
      </div>
    </div>
  );
};

export default Home;
