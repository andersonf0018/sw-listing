import { render, screen } from "@testing-library/react";
import type { DetailsItem } from "@/types/details";
import { DetailsBox } from "./details-box";

jest.mock("next/link", () => {
  const MockNextLink = ({ children, href }: { children: React.ReactNode; href: string }) => {
    return <a href={href}>{children}</a>;
  };

  MockNextLink.displayName = "NextLink";
  return MockNextLink;
});

describe("DetailsBox", () => {
  const mockTitle = "Character Details";
  const mockInformations: DetailsItem[] = [
    {
      title: "Personal Info",
      children: (
        <div>
          <p>Height: 172cm</p>
          <p>Mass: 77kg</p>
        </div>
      ),
    },
    {
      title: "Films",
      children: (
        <div>
          <p>A New Hope</p>
          <p>The Empire Strikes Back</p>
        </div>
      ),
    },
  ];

  it("renders the component with correct title", () => {
    render(<DetailsBox title={mockTitle} informations={mockInformations} />);

    expect(screen.getByText(mockTitle)).toBeInTheDocument();
  });

  it("renders all information sections correctly", () => {
    render(<DetailsBox title={mockTitle} informations={mockInformations} />);

    expect(screen.getByText("Personal Info")).toBeInTheDocument();
    expect(screen.getByText("Films")).toBeInTheDocument();

    expect(screen.getByText("Height: 172cm")).toBeInTheDocument();
    expect(screen.getByText("Mass: 77kg")).toBeInTheDocument();
    expect(screen.getByText("A New Hope")).toBeInTheDocument();
    expect(screen.getByText("The Empire Strikes Back")).toBeInTheDocument();
  });

  it("renders the back button with correct link", () => {
    render(<DetailsBox title={mockTitle} informations={mockInformations} />);

    const backButton = screen.getByText("Back to search");
    expect(backButton).toBeInTheDocument();

    const link = backButton.closest("a");
    expect(link).toHaveAttribute("href", "/");
  });
});
