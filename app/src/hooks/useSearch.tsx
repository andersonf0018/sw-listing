import { useEffect } from "react";
import { useQuery } from "@tanstack/react-query";
import { useResultsStore, useSearchBoxStore } from "@/stores";
import { api } from "@/api";
import { useDebounceValue } from "usehooks-ts";

export const useSearch = () => {
  const { search, selectedOption } = useSearchBoxStore();
  const { 
    setResults, 
    currentPage,
    setTotalResults
  } = useResultsStore();
  const [debouncedInput] = useDebounceValue(search, 350);

  const fetchData = async (page = 1) => {
    return api.get(
      `/${selectedOption?.toLowerCase()}/?search=${debouncedInput}&page=${page}`
    ).then((res) => res.data);
  };

  const { data, isLoading, error, refetch } = useQuery({
    queryKey: ["search", selectedOption, debouncedInput, currentPage],
    queryFn: () => fetchData(currentPage),
    enabled: debouncedInput.length >= 2 && !!selectedOption,
  });

  const fetchMore = async () => {
    const nextPage = currentPage + 1;
    const moreData = await fetchData(nextPage);
    return { data: moreData, nextPage };
  };

  useEffect(() => {
    if (data) {
      setResults(data);
      setTotalResults(data.count);
    }
  }, [data, setResults, setTotalResults]);

  return { 
    data, 
    isLoading, 
    error, 
    refetch,
    fetchMore
  };
};