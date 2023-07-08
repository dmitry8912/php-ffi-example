#include "library.h"

#include <sys/statvfs.h>

int arithmetical_operation(int left_operand, int right_operand, char operation) {

    if(operation == '+')
    {
        return left_operand + right_operand;
    }

    if(operation == '*')
    {
        return left_operand * right_operand;
    }
}

double get_free_space(const char* path)
{
    struct statvfs buf;

    if (statvfs(path, &buf) == -1) {
        return 0;
    }
    return ((double)buf.f_bavail * buf.f_bsize);
}



