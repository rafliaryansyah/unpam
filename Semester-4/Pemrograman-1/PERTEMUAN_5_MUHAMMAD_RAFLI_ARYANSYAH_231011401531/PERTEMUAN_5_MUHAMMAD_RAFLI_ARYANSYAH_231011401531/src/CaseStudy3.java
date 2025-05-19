import java.util.Scanner;

public class CaseStudy3 {
    public static void main(String[] args) {
        System.out.println("PERTEMUAN 5 - STUDY KASUS 3 - MUHAMMAD RAFLI ARYANSYAH - 231011401531");

        int defaultInput = 0;
        String pageInput = "";
        String username = "";
        String password = "";
        Scanner input = new Scanner(System.in);

        System.out.print("Masukan Username : "); // S1
        username = input.nextLine();

        if (username.equalsIgnoreCase("andi")) {
            System.out.print("Masukan Password : "); // S2
            password = input.nextLine();
            switch (password) {
                case "1234":
                    System.out.println("Kamu berada di halaman admin"); // S3
                    System.out.println("A. Menu A");
                    System.out.println("B. Menu B");
                    System.out.println("C. Menu C");
                    System.out.print("Masukkan pilihan:... ");
                    pageInput = input.nextLine();
                    switch (pageInput) {
                        case "a":
                            System.out.println("halaman A");
                            System.exit(0);
                            break;
                        case "b":
                            System.out.println("halaman B");
                            System.exit(0);
                            break;
                        case "c":
                            System.out.println("halaman C");
                            System.exit(0);
                            break;
                        default:
                            System.exit(0);
                            break;
                    }
                    break;
                default:
                    System.out.println("Password salah.");// S4
                    break;
            }
        } else {
            System.out.print("Salah username " + username); // S8
        }
    }
}
