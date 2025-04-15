import { render, screen } from "@testing-library/react";
import { ErrorBox } from "./error-box";

jest.mock("next/link", () => {
  const MockNextLink = ({ children, href }: { children: React.ReactNode; href: string }) => {
    return <a href={href}>{children}</a>;
  };

  MockNextLink.displayName = "NextLink";
  return MockNextLink;
});

describe("ErrorBox", () => {
  const mockError = "API request failed";

  it("renders the error message correctly", () => {
    render(<ErrorBox error={mockError} />);
    
    expect(screen.getByText(/oops! something went wrong:/i)).toBeInTheDocument();
    expect(screen.getByText(mockError)).toBeInTheDocument();
  });

  it("renders a back button with correct link", () => {
    render(<ErrorBox error={mockError} />);
    
    const backButton = screen.getByText(/back to search/i);
    expect(backButton).toBeInTheDocument();
    
    const link = backButton.closest("a");
    expect(link).toHaveAttribute("href", "/");
  });

  it("has proper styling for error message", () => {
    render(<ErrorBox error={mockError} />);
    
    const errorHeading = screen.getByText(/oops! something went wrong:/i);
    expect(errorHeading).toHaveClass("text-red-600");
    
    const errorMessage = screen.getByText(mockError);
    expect(errorMessage).toHaveClass("text-black");
  });
});
