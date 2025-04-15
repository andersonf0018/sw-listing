"use client";
import { ResultsItem } from "./results-item";
import { ApiResponse } from "@/types";
import { Person, Film } from "@/types/api";
import { Button } from "../ui";
import { useState } from "react";

interface ResultsBoxProps {
  results: ApiResponse<Person | Film>;
  isSearching?: boolean;
  onViewMore?: () => Promise<void>;
}

export const ResultsBox = ({
  results,
  isSearching,
  onViewMore,
}: ResultsBoxProps) => {
  const [isLoadingMore, setIsLoadingMore] = useState(false);
  const isPerson = (result: Person | Film) => "name" in result;
  const resultType = (result: Person | Film) =>
    isPerson(result) ? "person" : "film";

  const handleViewMore = async () => {
    if (!onViewMore) return;
    setIsLoadingMore(true);
    try {
      await onViewMore();
    } finally {
      setIsLoadingMore(false);
    }
  };

  return (
    <div className="flex flex-col h-full overflow-y-auto">
      <h2 className="text-lg font-bold">Results ({results.results.length})</h2>
      <hr className="border-t border-gray-200 my-3" />
      <div className="h-full max-h-[29rem] overflow-y-auto">
        {results.results.length > 0 ? (
          <div className="flex flex-col gap-2">
            {results?.results?.map((result) => {
              const displayName = isPerson(result) ? result.name : result.title;
              return (
                <ResultsItem
                  key={`${resultType(result)}-${result.id}`}
                  name={displayName}
                  id={result.id}
                  type={resultType(result)}
                />
              );
            })}
          </div>
        ) : (
          <div className="flex flex-col h-full justify-center items-center">
            <p className="text-sm text-center font-bold text-gray-400">
              {isSearching
                ? "Searching..."
                : "There are zero matches.\nUse the form to search for People or Movies"}
            </p>
          </div>
        )}
        {results.next && (
          <div className="flex justify-center mt-4">
            <Button
              className="font-bold uppercase hover:cursor-pointer"
              onClick={handleViewMore}
              disabled={isLoadingMore}
            >
              {isLoadingMore ? "Loading..." : "View More"}
            </Button>
          </div>
        )}
      </div>
    </div>
  );
};
