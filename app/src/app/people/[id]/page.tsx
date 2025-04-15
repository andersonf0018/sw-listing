"use client";

import { DetailsBox, ErrorBox, LoadingBox } from "@/components";
import { usePeople } from "@/hooks";
import Link from "next/link";
import { useParams } from "next/navigation";

const PeoplePage = () => {
  const { id } = useParams();
  const { data: person, isLoading, error } = usePeople(id as string);

  if (isLoading) return <LoadingBox />;
  if (error) return <ErrorBox error={error.message} />;

  const details = [
    { title: "Birth Year", value: person?.birth_year },
    { title: "Gender", value: person?.gender },
    { title: "Eye Color", value: person?.eye_color },
    { title: "Hair Color", value: person?.hair_color },
    { title: "Height", value: person?.height },
    { title: "Mass", value: person?.mass },
  ];

  const informations = [
    {
      title: "Details",
      children: (
        <div>
          {details.map((detail) => (
            <div key={detail.title}>
              <p className="text-md">
                <span className="font-bold">{detail.title}:</span>{" "}
                {detail.value}
              </p>
            </div>
          ))}
        </div>
      ),
    },
    {
      title: "Movies",
      children: (
        <div>
          {person?.films_data?.map((film, index) => (
            <span key={film.id}>
              {index > 0 && ", "}
              <Link
                href={`/movies/${film.id}`}
                className="text-blue-500 hover:underline"
              >
                {film.title}
              </Link>
            </span>
          ))}
        </div>
      ),
    },
  ];

  return (
    <div className="bg-white p-8 mx-8 md:mx-auto max-w-screen-md">
      <DetailsBox title={person?.name ?? ""} informations={informations} />
    </div>
  );
};

export default PeoplePage;
