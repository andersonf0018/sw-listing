import { useQuery } from "@tanstack/react-query";
import { api } from "@/api";
import { Film } from "@/types";

export const useMovies = (id: string) => {
  return useQuery({
    queryKey: ["movie", id],
    queryFn: async () => {
      const response = await api.get(`/movies/${id}`);
      return response.data as Film;
    },
  });
};
