"use client";

import Link from "next/link";
import { useParams } from "next/navigation";
import { DetailsBox, ErrorBox, LoadingBox } from "@/components";
import { useMovies } from "@/hooks/useMovies";

const PeoplePage = () => {
  const { id } = useParams();
  const { data: movie, isLoading, error } = useMovies(id as string);

  if (isLoading) return <LoadingBox />;
  if (error) return <ErrorBox error={error.message} />;

  const informations = [
    {
      title: "Opening Crawl",
      children: (
        <div>
          <p className="text-md">{movie?.opening_crawl}</p>
        </div>
      ),
    },
    {
      title: "Characters",
      children: (
        <div>
          {movie?.characters_data?.map((character, index) => (
            <span key={character.id}>
              {index > 0 && ", "}
              <Link
                href={`/people/${character.id}`}
                className="text-blue-500 hover:underline"
              >
                {character.name}
              </Link>
            </span>
          ))}
        </div>
      ),
    },
  ];

  return (
    <div className="bg-white p-8 mx-8 md:mx-auto max-w-screen-md">
      <DetailsBox title={movie?.title ?? ""} informations={informations} />
    </div>
  );
};

export default PeoplePage;
