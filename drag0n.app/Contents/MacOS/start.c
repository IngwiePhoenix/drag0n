#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <errno.h>

int main(int argc, char *argv[]) {

	char *fname = "/Users/Ingwie/d0info.cout";
	FILE *pfile;
	char *cwd;
	int x;
	char string[100];

	cwd=getcwd(0,0);

	if(!cwd) {
		fprintf(stderr, "getcwd failed: %s\n", strerror(errno));
	} else {
		pfile = fopen(fname, "w");
		if(pfile == NULL) {
			printf("Error opening %s for writing. Program terminated.", fname);
		} else {
		   	for(x=1; x<argc; x++) {
		   		strcat(string, argv[x]);
		   		strcat(string, "\n");
			}
			fputs(string, pfile);			
		}
		fclose(pfile);	
	}
	
	return 0;

//	return system("ls ./ >/Users/Ingwie/d0info.cout");
}